<?php
/**
 * User: LEOBA
 */


/**
 * required file of all configuration app
 */
require 'config.php';

require APP . DS . 'core' . DS . 'Autoloader.php';
\App\Core\Autoloader::autoloader();

use App\Core\Router\Router;
#required extend file
require APP . DS . 'lib' . DS . 'functions.php';

/**
 * required the file auloader 
 */

//require ROOT . DS . 'vendor' . DS . 'autoload.php';


$uri = $_SERVER['REQUEST_URI'];
if (empty($uri) || $uri === '/') {
    $uri = 'index';
}
$router = new Router($uri);


#Routing register all path

$router->get('/connexion/deconnexion', 'connexion#deconnexion');
$router->get('/connexion', 'connexion#index', 'auth');
$router->post('/connexion', 'connexion#index');
$router->get('index', 'index#index');

#produits
$router->get('/produit', 'produit#index', 'produit');
$router->get('/produit/add', 'produit#add', 'produit#add');
$router->get('/produit/imprimer', 'produit#imprimer');
$router->post('/produit/add', 'produit#add');
$router->get('/produit/loadAdd', 'produit#loadAdd');
$router->post('/produit/loadAdd', 'produit#loadAdd');
$router->post('/produit/laodDataForDeleteProduit', 'produit#laodDataForDeleteProduit');
$router->post('/produit/delete', 'produit#delete');
$router->post('/produit/edit', 'produit#edit');
$router->post('/produit/trieProduit', 'produit#trieProduit');
$router->post('/produit/showAllInfosProduit', 'produit#showAllInfosProduit');
$router->post('/produit/laodForEditeProduit', 'produit#laodForEditeProduit');

#vente
$router->get('/vente/all', 'vente#all');
$router->get('/vente/add', 'vente#add', 'vente#add');
$router->get('/vente/mesventes', 'vente#mesventes', 'vente#mesventes');
$router->get('/vente/endProcess', 'vente#endProcess');
$router->get('/vente/loadNextPart', 'vente#loadNextPart');
$router->post('/vente/add', 'vente#add');
$router->post('/vente/mesventes', 'vente#mesventes');
$router->post('/vente/loadProduitForTableAddVente', 'vente#loadProduitForTableAddVente');
$router->post('/vente/loadInfosProduitVente', 'vente#loadInfosProduitVente');
$router->post('/vente/addProduitInVente', 'vente#addProduitInVente');
$router->post('/vente/removeProduitInVente', 'vente#removeProduitInVente');
$router->post('/vente/imprimer', 'vente#imprimer');
$router->post('/vente/trieVente', 'vente#trieVente');
$router->post('/vente/impression_vente', 'vente#impression_vente');
$router->post('/vente/showAllInfosMesventes', 'vente#showAllInfosMesventes');

#achat
$router->get('achat', 'achat#index', 'achat');
$router->get('achat/add', 'achat#add');
$router->post('achat', 'achat#index', 'achat');
$router->post('achat/add', 'achat#add');
$router->post('achat/laodForShowCommande', 'achat#laodForShowCommande');
$router->post('achat/loadProduitOffre', 'achat#loadProduitOffre');
$router->post('achat/loadProduitForAddOffre', 'achat#loadProduitForAddOffre');
$router->post('achat/addProduitInCommand', 'achat#addProduitInCommand');
$router->post('achat/removeProduitOffre', 'achat#removeProduitOffre');
$router->post('achat/livres', 'achat#livres');
$router->post('achat/trieAchat', 'achat#trieAchat');
$router->post('achat/loadForEdit', 'achat#loadForEdit');
$router->post('achat/edit', 'achat#edit');

#mouvement
$router->get('mouvement', 'Mouvement#index');
$router->get('mouvement/stock', 'Mouvement#stock');
$router->get('mouvement/entree', 'Mouvement#entree');
$router->post('mouvement/entree', 'Mouvement#entree');
//$router->get('mouvement/impressionMouvement', 'mouvement#imprimer');
//$router->post('mouvement/trieEntree1', 'mouvement#trieEntree1');
//$router->post('mouvement/trieSortie1', 'mouvement#trieSortie1');
//$router->post('mouvement/imprimerStock', 'mouvement#imprimerStock');
//$router->post('mouvement/triByProduit', 'mouvement#triByProduit');
$router->post('mouvement/loadProduitForEntree', 'mouvement#loadProduitForEntree');
$router->post('mouvement/loadInfosProduitEntree', 'mouvement#loadInfosProduitEntree');
//$router->post('mouvement/addInEntree', 'mouvement#addInEntree');
$router->post('mouvement/removeProduitFromCommande', 'mouvement#removeProduitFromCommande');
$router->post('mouvement/addLivraisonOnView', 'mouvement#addLivraisonOnView');

#magasin
$router->get('magasin/mouvement', 'magasin#mouvement');
$router->get('magasin/stock', 'magasin#stock');
$router->get('magasin/imprimer', 'magasin#imprimer');
$router->post('magasin/trieEntree1', 'magasin#trieEntree1');
$router->post('magasin/trieSortie1', 'magasin#trieSortie1');
$router->post('magasin/imprimerStock', 'magasin#imprimerStock');
$router->post('magasin/triByProduit', 'magasin#triByProduit');

#fournisseur
$router->get('fournisseur', 'fournisseur#index', 'fournisseur');
$router->post('fournisseur/add', 'fournisseur#add');
$router->post('fournisseur/laodDataForDeleteFournisseur', 'fournisseur#laodDataForDeleteFournisseur');
$router->post('fournisseur/delete', 'fournisseur#delete');
$router->post('fournisseur/laodForEditFournisseur', 'fournisseur#laodForEditFournisseur');
$router->post('fournisseur/edit', 'fournisseur#edit');
	
#client
$router->get('client', 'client#index', 'client');
$router->post('client/add', 'client#add');
$router->post('client/laodDataForDeleteClient', 'client#laodDataForDeleteClient');
$router->post('client/laodForEditClient', 'client#laodForEditClient');
$router->post('client/edit', 'client#edit');
$router->post('client/delete', 'client#delete');

#user
$router->get('user', 'user#index');
$router->get('user/connexion', 'user#connexion');
$router->get('user/add', 'user#add');
$router->get('user/laodDataForDeleteUser', 'user#laodDataForDeleteUser');
$router->get('user/loadForContent', 'user#loadForContent');
$router->get('user/loadForEditUser', 'user#loadForEditUser');
$router->get('user/edit', 'user#edit');

#famille
$router->get('famille', 'famille#index', 'unite');
$router->post('famille/add', 'famille#add');
$router->post('famille/edit', 'famille#edit');
$router->post('famille/delete', 'famille#delete');
$router->post('famille/laodDataForDeleteFamille', 'famille#laodDataForDeleteFamille');
$router->post('famille/loadForEditFamille', 'famille#loadForEditFamille');

#unite
$router->get('unite', 'unite#index', 'unite');
$router->post('unite/add', 'unite#add');
$router->post('unite/edit', 'unite#edit');
$router->post('unite/delete', 'unite#delete');
$router->post('unite/laodDataForDeleteUnite', 'unite#laodDataForDeleteUnite');
$router->post('unite/laodForEditUnite', 'unite#laodForEditUnite');

#statistique

$router->get('stat/produitplus', 'stat#produitplus');
$router->get('stat/produitmoins', 'stat#produitmoins');
$router->get('stat/expiration', 'stat#expiration');


#parameter

$router->get('params', 'params#index');
$router->get('backup', 'backup#index');

#offshore
$router->get('offshore', 'offshore#index', 'offshore');
$router->get('/offshore/loadAdd', 'offshore#loadAdd');
$router->post('/offshore/loadAdd', 'offshore#loadAdd');
$router->get('/offshore/add', 'offshore#add', 'offshore#add');
$router->get('/offshore/imprimer', 'offshore#imprimer');
$router->post('/offshore/add', 'offshore#add');
$router->post('/offshore/laodDataForDeleteOffshore', 'offshore#laodDataForDeleteOffshore');
$router->post('/offshore/delete', 'offshore#delete');
$router->post('/offshore/edit', 'offshore#edit');
$router->post('/offshore/trieOffshore', 'offshore#trieOffshore');
$router->post('/offshore/showAllInfosOffshore', 'offshore#showAllInfosOffshore');
$router->post('/offshore/laodForEditeOffshore', 'offshore#laodForEditeOffshore');



#commande
$router->get('commande', 'commande#index', 'commande');
$router->get('commande/ajout', 'commande#ajout');
$router->post('commande/ajout', 'commande#ajout');
$router->get('commande/selectproduit', 'commande#selectproduit');
$router->post('commande/addProduitToCommande', 'commande#addProduitToCommande');
$router->post('commande/deletProduitFromCommande', 'commande#deletProduitFromCommande');
$router->get('commande/voir/:id', 'commande#voir');
$router->get('commande/imprimer/:id', 'commande#imprimer');


/**
 * run app
 */
$router->run();