<?php
/*
|--------------------------------------------------------------------------
| TypeRocket Routes
|--------------------------------------------------------------------------
|
| Manage your web routes here.
|
*/

tr_route()->post('/annonce/send', 'annonce@member');


tr_route()->post('/annonce/posttuler', 'postuler@member');
tr_route()->get('/annonce/posttuler', 'postuler@member');


tr_route()->post('/candidat/login', 'login@member');
tr_route()->post('/candidat/register', 'register@member');
tr_route()->get('/candidat/email/confirmation', 'confirmation@member');

tr_route()->get('/candidat/candidature/create', 'candudatureCreated@member');
tr_route()->get('/candidat/candidatures', 'candudature@member');



tr_route()->get('/candidat/logout', 'logout@member');

tr_route()->get('/candidat/profil', 'profil@member');
tr_route()->post('/candidat/profil', 'profil@member');

tr_route()->get('/candidat/alerte', 'alerte@member');

tr_route()->get('/candidat/print/{id}', 'pdfgenerated@member');


tr_route()->get('/candidat/curriculum/create', 'createcv@member');
tr_route()->post('/candidat/curriculum/create', 'createcv@member');

tr_route()->get('/candidat/curriculum/edit', 'editcv@member');
tr_route()->post('/candidat/curriculum/edit', 'editcv@member');

tr_route()->get('/candidat/curriculum', 'curriculum@member');

tr_route()->get('/candidat/reset', 'reset@member');
tr_route()->post('/candidat/reset', 'reset@member');


