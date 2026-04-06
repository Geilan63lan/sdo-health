import Alpine from 'alpinejs';
import Flux from '@livewire/flux';

// Make Alpine available globally for Livewire to use
window.Alpine = Alpine;

// Start Alpine for non-Livewire pages (like login)
// Flux components will be registered by the Flux import above
Alpine.start();
