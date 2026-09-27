import { initMenus } from './menu';
import { initRechercheOffres } from './recherche';
import { initTableauxDefilants } from './tableaux';

// Chaque module ne s'active que si les éléments dont il a besoin sont présents sur la page
initMenus();
initRechercheOffres();
initTableauxDefilants();
