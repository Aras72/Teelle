export const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
export const pointerTilt = (x, y, width, height) => ({
    x: clamp((y / Math.max(height, 1) - 0.5) * 2, -1, 1) * 0.13962634,
    y: clamp((x / Math.max(width, 1) - 0.5) * 2, -1, 1) * 0.13962634,
});
export const damp = (current, target, dt) => current + (target - current) * (1 - Math.exp(-8 * dt));
export const qualityDpr = (dpr, lowPower) => Math.min(dpr || 1, lowPower ? 1 : 1.75);
