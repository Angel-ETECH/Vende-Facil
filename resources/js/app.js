import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, Heart, Store, Bell, Flag, ChartNoAxesCombined, ChevronLeft, ChevronRight, Utensils, Shirt, Monitor, Sparkles, House, Gift, Wrench, Shapes, ArrowUpRight, ArrowRight, MapPin, BadgeCheck } from 'lucide';
import productCarousel from './product-carousel';

window.Alpine = Alpine;
Alpine.data('productCarousel', productCarousel);

Alpine.start();
createIcons({ icons: { Heart, Store, Bell, Flag, ChartNoAxesCombined, ChevronLeft, ChevronRight, Utensils, Shirt, Monitor, Sparkles, House, Gift, Wrench, Shapes, ArrowUpRight, ArrowRight, MapPin, BadgeCheck } });
