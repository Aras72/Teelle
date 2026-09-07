import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';
import * as math from '../../resources/js/marble-math.js';

// Exercise real island event handlers with deterministic rendering/browser adapters.
function harness(reduce = false, fail = false) {
    class Target extends EventTarget {
        dataset = {}; hidden = false; clientWidth = 300;
        classList = { add() {}, remove() {} };
        getBoundingClientRect() { return { left: 0, top: 0, width: 300, height: 300 }; }
        setPointerCapture(id) { this.capture = id; }
        hasPointerCapture(id) { return this.capture === id; }
        releasePointerCapture() { this.capture = null; }
        setAttribute() {} remove() { this.removed = true; }
        emit(type, props = {}) { const event = new Event(type, { cancelable: true }); Object.assign(event, props); this.dispatchEvent(event); }
    }
    const control = new Target(), stage = new Target(), document = new Target(), window = new Target();
    const reduced = new Target(); reduced.matches = reduce;
    stage.querySelector = () => control; stage.insertBefore = () => {};
    let group, renderer, renders = 0, disposed = 0, frameId = 0;
    const frames = new Map();
    class Object3D {
        children = []; position = { set() {} }; rotation = { set(x, y, z) { this.x = x; this.y = y; this.z = z; } };
        add(child) { this.children.push(child); }
        traverse(fn) { fn(this); this.children.forEach(child => child.traverse(fn)); }
    }
    class Resource { dispose() { disposed++; } setAttribute() {} setIndex() {} computeVertexNormals() {} }
    class Renderer extends Resource {
        domElement = new Target();
        constructor() { super(); if (fail) throw Error('WebGL unavailable'); renderer = this; }
        setPixelRatio() {} setClearColor() {} setSize() {} render() { renders++; }
    }
    const THREE = {
        WebGLRenderer: Renderer, Scene: Object3D,
        Group: class extends Object3D { constructor() { super(); group = this; } },
        PerspectiveCamera: class extends Object3D { updateProjectionMatrix() {} },
        HemisphereLight: Object3D, DirectionalLight: Object3D,
        Mesh: class extends Object3D { constructor(geometry, material) { super(); this.geometry = geometry; this.material = material; } },
        SphereGeometry: Resource, MeshPhysicalMaterial: Resource, BufferGeometry: Resource, Float32BufferAttribute: Resource,
        PMREMGenerator: class extends Resource { fromScene() { return new Resource(); } },
    };
    const context = vm.createContext({ THREE, RoomEnvironment: Resource, ...math, document, window,
        matchMedia: () => reduced, navigator: { hardwareConcurrency: 2 }, devicePixelRatio: 3,
        AbortController, ResizeObserver: class { observe() {} disconnect() {} },
        IntersectionObserver: class { observe() {} disconnect() {} },
        requestAnimationFrame: callback => { frames.set(++frameId, callback); return frameId; },
        cancelAnimationFrame: id => frames.delete(id),
    });
    const source = readFileSync(new URL('../../resources/js/marble.js', import.meta.url), 'utf8')
        .replace(/^import .*;\r?\n/gm, '').replace('export function mountMarble', 'function mountMarble');
    vm.runInContext(source, context);
    const dispose = context.mountMarble(stage);
    return { control, stage, document, window, reduced, frames, group, renderer, dispose,
        get renders() { return renders; }, get disposed() { return disposed; },
        tick(time) { const batch = [...frames.values()]; frames.clear(); batch.forEach(fn => fn(time)); },
    };
}

test('keyboard rotates, Home resets, Escape stops, explicit input resumes', () => {
    const h = harness();
    h.control.emit('keydown', { key: 'ArrowRight' }); assert.equal(h.group.rotation.y, 0.4);
    h.control.emit('keydown', { key: 'Home' }); assert.equal(h.group.rotation.y, 0.2);
    h.control.emit('keydown', { key: 'Escape' }); assert.equal(h.frames.size, 0);
    h.control.emit('keydown', { key: 'Enter' }); assert.equal(h.frames.size, 1);
    h.dispose();
});
test('reduced motion has no loop, touch capture rotates and cancellation releases', () => {
    const h = harness(true); assert.equal(h.frames.size, 0);
    h.control.emit('pointerdown', { isPrimary: true, button: 0, pointerId: 7, clientX: 20, clientY: 20 });
    assert.equal(h.control.capture, 7);
    h.control.emit('pointermove', { pointerId: 7, clientX: 80, clientY: 40 });
    assert.ok(h.group.rotation.y > 0.2);
    h.control.emit('pointercancel'); assert.equal(h.control.capture, null);
    assert.equal(h.frames.size, 0); h.dispose();
});
test('hidden tab and bfcache pause/resume; navigation disposes resources and listeners', () => {
    const h = harness(); h.document.hidden = true; h.document.emit('visibilitychange');
    assert.equal(h.frames.size, 0);
    h.document.hidden = false; h.document.emit('visibilitychange'); assert.equal(h.frames.size, 1);
    h.window.emit('pagehide', { persisted: true }); assert.equal(h.frames.size, 0);
    h.window.emit('pageshow', { persisted: true }); assert.equal(h.frames.size, 1);
    h.document.emit('livewire:navigating'); assert.equal(h.frames.size, 0);
    assert.ok(h.disposed >= 12); assert.equal(h.renderer.domElement.removed, true);
    const renders = h.renders; h.control.emit('keydown', { key: 'ArrowRight' }); assert.equal(h.renders, renders);
});
test('WebGL failure and context loss retain fallback and stop rendering', () => {
    const failed = harness(false, true); assert.equal(failed.stage.dataset.marbleState, 'fallback');
    assert.equal(failed.control.hidden, true); assert.equal(failed.frames.size, 0);
    const h = harness(); h.renderer.domElement.emit('webglcontextlost');
    assert.equal(h.stage.dataset.marbleState, 'fallback'); assert.equal(h.frames.size, 0);
    assert.equal(h.control.hidden, true); h.dispose();
});
test('sustained slow frames lower rendering quality', () => {
    const h = harness(); for (let i = 1; i <= 122; i++) h.tick(i * 40);
    assert.equal(h.stage.dataset.marbleQuality, 'reduced');
    assert.equal(h.stage.dataset.marbleFps, '25.0'); h.dispose();
});
