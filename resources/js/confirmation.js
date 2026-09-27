/**
 * Popup de confirmation stylée (composant <x-modale-confirmation>) à la place de confirm().
 *
 * Un formulaire porte ses textes dans des attributs data-confirmation-* :
 *   data-confirmation-titre, data-confirmation-message,
 *   data-confirmation-elements (liste JSON), data-confirmation-bouton.
 * À l'envoi, la popup s'ouvre ; le formulaire n'est envoyé que si l'utilisateur confirme.
 *
 * Amélioration progressive : le formulaire garde un onsubmit="return confirm(...)"
 * utilisé seulement sans JavaScript ; on le retire ici au profit de la popup.
 */
export function initConfirmations() {
    const modale = document.getElementById('modale-confirmation');
    const formulaires = document.querySelectorAll('form[data-confirmation-titre]');
    if (!modale || formulaires.length === 0) {
        return;
    }

    const titre = modale.querySelector('#modale-confirmation-titre');
    const message = modale.querySelector('#modale-confirmation-message');
    const liste = modale.querySelector('#modale-confirmation-elements');
    const boutonValider = modale.querySelector('[data-confirmation-valider]');
    let formulaireEnAttente = null;

    formulaires.forEach((formulaire) => {
        formulaire.removeAttribute('onsubmit');

        formulaire.addEventListener('submit', (event) => {
            event.preventDefault();
            formulaireEnAttente = formulaire;

            // textContent (et non innerHTML) : les noms saisis par les utilisateurs ne sont jamais interprétés comme du HTML
            titre.textContent = formulaire.dataset.confirmationTitre;
            message.textContent = formulaire.dataset.confirmationMessage || '';
            boutonValider.textContent = formulaire.dataset.confirmationBouton || 'Confirmer';

            liste.replaceChildren(...JSON.parse(formulaire.dataset.confirmationElements || '[]').map((texte) => {
                const element = document.createElement('li');
                element.className = 'flex items-start gap-2';
                const puce = document.createElement('span');
                puce.className = 'mt-2 w-1.5 h-1.5 rounded-full bg-red-400 shrink-0';
                puce.setAttribute('aria-hidden', 'true');
                element.append(puce, texte);
                return element;
            }));

            modale.showModal();
        });
    });

    boutonValider.addEventListener('click', () => {
        modale.close();
        // submit() n'émet pas l'événement « submit » : pas de nouvelle ouverture de la popup
        formulaireEnAttente?.submit();
    });

    modale.querySelector('[data-confirmation-annuler]').addEventListener('click', () => modale.close());

    // Un clic sur le fond sombre (en dehors de la boîte) ferme la popup
    modale.addEventListener('click', (event) => {
        if (event.target === modale) {
            modale.close();
        }
    });
}
