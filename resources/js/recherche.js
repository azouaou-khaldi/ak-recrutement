/**
 * Recherche d'offres en direct.
 *
 * Amélioration progressive : sans JavaScript, le formulaire recharge la page
 * comme d'habitude. Avec JavaScript, les résultats se mettent à jour pendant
 * la frappe, sans recharger la page.
 *
 * Le serveur (OffreController@index) détecte la requête AJAX grâce à l'en-tête
 * X-Requested-With et renvoie uniquement le HTML de la liste
 * (resources/views/offres/partials/resultats.blade.php), déjà échappé par Blade.
 */
const DELAI_ANTI_REBOND = 300; // millisecondes

export function initRechercheOffres() {
    const formulaire = document.querySelector('[data-recherche-offres]');
    // Ce script est chargé sur toutes les pages : on s'arrête si on n'est pas sur la page des offres
    if (!formulaire) {
        return;
    }

    const champ = formulaire.querySelector('input[name="recherche"]');
    const resultats = document.getElementById(formulaire.dataset.rechercheOffres);
    let minuteur = null;
    let requeteEnCours = null;

    async function rechercher() {
        const terme = champ.value.trim();
        const url = new URL(formulaire.action);
        if (terme) {
            url.searchParams.set('recherche', terme);
        }

        // Si l'utilisateur tape vite, on annule la requête précédente devenue inutile
        requeteEnCours?.abort();
        const controleur = new AbortController();
        requeteEnCours = controleur;

        // Résultats grisés pendant le chargement, pour montrer que la recherche est en cours
        resultats.classList.add('opacity-50');

        try {
            const reponse = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controleur.signal,
            });

            if (!reponse.ok) {
                throw new Error(`Erreur serveur : ${reponse.status}`);
            }

            // innerHTML sans risque XSS ici : le HTML vient de notre serveur et Blade a déjà échappé le texte
            resultats.innerHTML = await reponse.text();

            // Met à jour l'adresse (lien partageable, bouton retour) sans recharger la page
            history.replaceState(null, '', url);
        } catch (erreur) {
            // Requête annulée volontairement (nouvelle frappe) : ce n'est pas une vraie erreur
            if (erreur.name === 'AbortError') {
                return;
            }
            // En cas de problème, on revient à la recherche classique
            formulaire.submit();
        } finally {
            // On enlève le gris seulement si aucune requête plus récente n'est en cours
            if (requeteEnCours === controleur) {
                resultats.classList.remove('opacity-50');
            }
        }
    }

    // Anti-rebond : on attend que l'utilisateur arrête de taper avant d'interroger le serveur
    champ.addEventListener('input', () => {
        clearTimeout(minuteur);
        minuteur = setTimeout(rechercher, DELAI_ANTI_REBOND);
    });

    // Touche Entrée ou bouton « Rechercher » : recherche immédiate, sans rechargement
    formulaire.addEventListener('submit', (event) => {
        event.preventDefault();
        clearTimeout(minuteur);
        rechercher();
    });
}
