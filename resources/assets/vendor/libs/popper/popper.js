import { createPopper } from '@popperjs/core';

// Required to enable animations on dropdowns/tooltips/popovers
// Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false

try {
  window.Popper = { createPopper };
} catch (e) {}

export { createPopper };
