import { test } from 'node:test';
import assert from 'node:assert/strict';
import { clamp, damp, pointerTilt, qualityDpr } from '../../resources/js/marble-math.js';

test('pointer tilt is neutral at center and bounded to eight degrees', () => {
    assert.deepEqual(pointerTilt(50, 50, 100, 100), { x: 0, y: 0 });
    assert.deepEqual(pointerTilt(-1000, 1000, 100, 100), { x: 0.13962634, y: -0.13962634 });
    assert.ok(Number.isFinite(pointerTilt(0, 0, 0, 0).x));
});
test('damping is frame-rate independent and never overshoots', () => {
    const half = damp(0, 1, 0.01);
    assert.ok(Math.abs(damp(half, 1, 0.01) - damp(0, 1, 0.02)) < 1e-12);
    assert.equal(damp(0, 1, 0), 0);
    assert.ok(damp(0, 1, 1) <= 1);
});
test('DPR and momentum caps protect lower-power devices', () => {
    assert.equal(qualityDpr(3, true), 1);
    assert.equal(qualityDpr(3, false), 1.75);
    assert.equal(qualityDpr(0, false), 1);
    assert.equal(clamp(12, -3, 3), 3);
    assert.equal(clamp(-12, -3, 3), -3);
});
