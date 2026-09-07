import * as THREE from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';
import { clamp, damp, pointerTilt, qualityDpr } from './marble-math';

export function mountMarble(stage) {
    const control = stage.querySelector('[data-marble-control]');
    const reduced = matchMedia('(prefers-reduced-motion: reduce)');
    const abort = new AbortController();
    const on = (target, event, handler) => target.addEventListener(event, handler, { signal: abort.signal });
    let renderer;
    try {
        renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
    } catch {
        stage.dataset.marbleState = 'fallback';
        control.hidden = true;
        return () => abort.abort();
    }
    const lowPower = (navigator.hardwareConcurrency || 4) <= 4 || (navigator.deviceMemory || 8) <= 4;
    let dpr = qualityDpr(devicePixelRatio, lowPower);
    renderer.setPixelRatio(dpr);
    renderer.setClearColor(0, 0);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 0.9;
    renderer.domElement.className = 'marble-canvas';
    renderer.domElement.setAttribute('aria-hidden', 'true');
    stage.insertBefore(renderer.domElement, control);

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 20);
    camera.position.set(0, 0, 3.8);
    const pmrem = new THREE.PMREMGenerator(renderer);
    const room = new RoomEnvironment();
    const environment = pmrem.fromScene(room, 0.04);
    scene.environment = environment.texture;
    room.dispose();
    pmrem.dispose();
    const group = new THREE.Group();
    group.rotation.set(-0.25, 0.2, -0.35);
    scene.add(group);
    scene.add(new THREE.HemisphereLight('#fff8e4', '#174e50', 0.8));
    const keyLight = new THREE.DirectionalLight('#ffffff', 2);
    keyLight.position.set(-3, 4, 5); scene.add(keyLight);
    const detail = lowPower ? 40 : 64;
    const shell = new THREE.Mesh(new THREE.SphereGeometry(1, detail, detail), new THREE.MeshPhysicalMaterial({
        color: '#d5f0e9', metalness: 0, roughness: 0.045,
        transmission: 0.35, transparent: true, opacity: 0.4, depthWrite: false,
        thickness: 0.15, ior: 1.5, clearcoat: 1,
        attenuationColor: '#9bc9bf', attenuationDistance: 3.8, envMapIntensity: 1.5,
    }));
    group.add(shell);

    // Solid, tapered ribbons inside the glass, with independently visible back faces.
    for (const [index, color] of ['#12636b', '#ecb94c', '#c9253e', '#238b86'].entries()) {
        const positions = [], indices = [];
        const rows = lowPower ? 56 : 96, columns = 12;
        for (let i = 0; i <= rows; i++) {
            const t = i / rows, y = (t * 2 - 1) * 0.91;
            const radius = Math.sqrt(Math.max(0, 0.84 ** 2 - y ** 2));
            const angle = t * Math.PI * 1.5 + index * Math.PI / 2;
            for (let j = 0; j <= columns; j++) {
                const across = (j / columns - 0.5) * 1.15;
                const a = angle + across;
                positions.push(radius * Math.cos(a), y, radius * Math.sin(a));
                if (i < rows && j < columns) {
                    const n = i * (columns + 1) + j;
                    indices.push(n, n + columns + 1, n + 1, n + 1, n + columns + 1, n + columns + 2);
                }
            }
        }
        const geometry = new THREE.BufferGeometry();
        geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
        geometry.setIndex(indices);
        geometry.computeVertexNormals();
        group.add(new THREE.Mesh(geometry, new THREE.MeshPhysicalMaterial({
            color, side: THREE.DoubleSide, roughness: 0.17, metalness: 0.25,
            clearcoat: 1, clearcoatRoughness: 0.08,
        })));
    }
    let disposed = false, failed = false, visible = true, frame = 0, lastTime = 0;
    let drag = null, velocity = { x: 0, y: 0 }, rotation = { x: 0, y: 0 };
    let tilt = { x: 0, y: 0 }, shownTilt = { x: 0, y: 0 }, paused = false;
    let measured = 0, samples = 0, slowSamples = 0;
    const render = () => {
        if (disposed || failed) return;
        group.rotation.set(-0.25 + rotation.x + shownTilt.x, 0.2 + rotation.y + shownTilt.y, -0.35);
        renderer.render(scene, camera);
    };
    const stop = () => { cancelAnimationFrame(frame); frame = 0; lastTime = 0; };
    const tick = (time) => {
        frame = 0;
        if (disposed || failed || document.hidden || !visible || reduced.matches || paused) return;
        const delta = lastTime ? Math.min((time - lastTime) / 1000, 0.05) : 1 / 60;
        if (lastTime) {
            measured += time - lastTime; samples++; slowSamples += time - lastTime > 24 ? 1 : 0;
            if (samples === 120) {
                stage.dataset.marbleFps = (1000 * samples / measured).toFixed(1);
                if (slowSamples > 30 && dpr > 0.75) {
                    dpr = Math.max(0.75, dpr - 0.25); renderer.setPixelRatio(dpr); resize();
                    stage.dataset.marbleQuality = 'reduced';
                }
                measured = 0; samples = 0; slowSamples = 0;
            }
        }
        lastTime = time;
        if (!drag) {
            rotation.y += (0.12 + velocity.y) * delta;
            rotation.x += velocity.x * delta;
            velocity.x *= Math.exp(-4 * delta); velocity.y *= Math.exp(-4 * delta);
        }
        shownTilt.x = damp(shownTilt.x, tilt.x, delta);
        shownTilt.y = damp(shownTilt.y, tilt.y, delta);
        render();
        frame = requestAnimationFrame(tick);
    };
    const start = () => {
        if (!frame && !disposed && !failed && !document.hidden && visible && !reduced.matches && !paused) frame = requestAnimationFrame(tick);
    };
    const resize = () => {
        if (disposed || failed) return;
        const { width, height } = stage.getBoundingClientRect();
        renderer.setSize(Math.max(1, width), Math.max(1, height), false);
        camera.aspect = width / Math.max(1, height); camera.updateProjectionMatrix(); render();
    };
    const observer = new ResizeObserver(resize); observer.observe(stage);
    const intersection = new IntersectionObserver(([entry]) => { visible = entry.isIntersecting; visible ? start() : stop(); });
    intersection.observe(stage);
    const cancelDrag = () => {
        if (drag && control.hasPointerCapture(drag.id)) control.releasePointerCapture(drag.id);
        drag = null; control.classList.remove('is-dragging'); velocity = { x: 0, y: 0 };
    };
    on(document, 'pointermove', (event) => {
        if (reduced.matches || drag || event.pointerType === 'touch') return;
        const box = stage.getBoundingClientRect();
        tilt = pointerTilt(event.clientX - box.left, event.clientY - box.top, box.width, box.height);
    });
    on(control, 'pointerdown', (event) => {
        if (!event.isPrimary || event.button !== 0 || failed) return;
        paused = false; start();
        drag = { id: event.pointerId, x: event.clientX, y: event.clientY, distance: 0 };
        velocity = { x: 0, y: 0 }; control.setPointerCapture(event.pointerId); control.classList.add('is-dragging');
    });
    on(control, 'pointermove', (event) => {
        if (!drag || drag.id !== event.pointerId) return;
        const dx = event.clientX - drag.x, dy = event.clientY - drag.y;
        const factor = Math.PI / Math.max(stage.clientWidth, 160);
        rotation.y += dx * factor; rotation.x += dy * factor;
        velocity = { x: clamp(dy * factor * 12, -3, 3), y: clamp(dx * factor * 12, -3, 3) };
        drag.distance += Math.abs(dx) + Math.abs(dy); drag.x = event.clientX; drag.y = event.clientY;
        render();
    });
    on(control, 'pointerup', (event) => {
        if (!drag || drag.id !== event.pointerId) return;
        const clicked = drag.distance < 5;
        drag = null; control.classList.remove('is-dragging');
        if (control.hasPointerCapture(event.pointerId)) control.releasePointerCapture(event.pointerId);
        if (reduced.matches) { velocity = { x: 0, y: 0 }; if (clicked) rotation.y += 0.25; render(); }
        else if (clicked) velocity.y = 2.8;
    });
    on(control, 'pointercancel', cancelDrag);
    on(control, 'lostpointercapture', () => { if (drag) cancelDrag(); });
    on(control, 'keydown', (event) => {
        if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'Escape', ' ', 'Enter'].includes(event.key)) return;
        event.preventDefault(); cancelDrag();
        if (event.key === 'Home') { rotation = { x: 0, y: 0 }; tilt = { x: 0, y: 0 }; shownTilt = { x: 0, y: 0 }; }
        paused = event.key === 'Escape';
        if (paused) { tilt = { x: 0, y: 0 }; shownTilt = { x: 0, y: 0 }; stop(); } else start();
        if (event.key === 'ArrowLeft') rotation.y -= 0.2;
        if (event.key === 'ArrowRight') rotation.y += 0.2;
        if (event.key === 'ArrowUp') rotation.x -= 0.2;
        if (event.key === 'ArrowDown') rotation.x += 0.2;
        if (event.key === ' ' || event.key === 'Enter') { if (reduced.matches) rotation.y += 0.25; else velocity.y = 2.8; }
        render();
    });
    on(document, 'visibilitychange', () => { cancelDrag(); document.hidden ? stop() : start(); });
    on(reduced, 'change', () => {
        cancelDrag(); tilt = { x: 0, y: 0 }; shownTilt = { x: 0, y: 0 };
        reduced.matches ? stop() : start(); render();
    });
    on(renderer.domElement, 'webglcontextlost', (event) => {
        event.preventDefault(); failed = true; stop(); cancelDrag();
        stage.dataset.marbleState = 'fallback'; control.hidden = true;
    });
    const dispose = () => {
        if (disposed) return;
        disposed = true; stop(); abort.abort(); observer.disconnect(); intersection.disconnect();
        scene.traverse((object) => { object.geometry?.dispose(); if (object.material) object.material.dispose(); });
        environment.dispose(); renderer.dispose(); renderer.domElement.remove();
        stage.dataset.marbleState = 'fallback'; control.hidden = true;
    };
    on(window, 'pagehide', (event) => { if (event.persisted) { cancelDrag(); stop(); } else dispose(); });
    on(document, 'livewire:navigating', dispose);
    on(window, 'pageshow', (event) => { if (event.persisted) start(); });
    resize(); stage.dataset.marbleState = 'ready'; control.hidden = false; start();
    return dispose;
}
