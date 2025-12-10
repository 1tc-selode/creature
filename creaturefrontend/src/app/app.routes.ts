import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { CreatureList } from './components/creature-list/creature-list';
import { CreatureDetail } from './components/creature-detail/creature-detail';
import { CreatureForm } from './components/creature-form/creature-form';
import { Gallery } from './components/gallery/gallery';
import { Contact } from './components/contact/contact';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: 'login', component: Login },
  { path: 'creatures', component: CreatureList },
  { path: 'creatures/new', component: CreatureForm, canActivate: [authGuard] },
  { path: 'creatures/:id', component: CreatureDetail },
  { path: 'creatures/:id/edit', component: CreatureForm, canActivate: [authGuard] },
  { path: 'creatures/:id/gallery', component: Gallery },
  { path: 'contact', component: Contact },
  { path: '**', redirectTo: '/login' }
];
