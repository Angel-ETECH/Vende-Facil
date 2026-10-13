import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, Heart, Store, Bell, Flag, ChartNoAxesCombined } from 'lucide';

window.Alpine = Alpine;

Alpine.start();
createIcons({ icons: { Heart, Store, Bell, Flag, ChartNoAxesCombined } });
