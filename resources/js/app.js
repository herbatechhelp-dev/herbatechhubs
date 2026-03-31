import { createIcons, icons } from 'lucide';

const bootLucide = () => {
	createIcons({
		icons,
		attrs: {
			'stroke-width': 1.8,
		},
	});
};

const applyStoredTheme = () => {
	const storedTheme = localStorage.getItem('shortcut-theme');

	if (! storedTheme) {
		return;
	}

	document.documentElement.classList.toggle('dark', storedTheme === 'dark');
};

const observeDomChanges = () => {
	const observer = new MutationObserver(() => {
		window.requestAnimationFrame(() => bootLucide());
	});

	observer.observe(document.body, {
		childList: true,
		subtree: true,
	});
};

document.addEventListener('DOMContentLoaded', () => {
	applyStoredTheme();
	bootLucide();
	observeDomChanges();
});

document.addEventListener('livewire:navigated', () => {
	applyStoredTheme();
	bootLucide();
});

document.addEventListener('shortcut-toast', bootLucide);
