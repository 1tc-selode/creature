import { Component } from '@angular/core';
import { RouterOutlet, RouterModule, Router } from '@angular/router';
import { AuthService } from './services/auth';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, RouterModule, CommonModule],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  title = 'creaturefrontend';
  isMobileMenuOpen = false;

  constructor(public authService: AuthService, private router: Router) {}

  toggleMobileMenu() {
    this.isMobileMenuOpen = !this.isMobileMenuOpen;
  }

  closeMobileMenu() {
    this.isMobileMenuOpen = false;
  }

  logout() {
    this.closeMobileMenu();
    this.authService.logout().subscribe({
      next: () => {
        this.router.navigate(['/login']);
      },
      error: () => {
        // Ha a backend logout sikertelen, akkor is navigáljunk vissza
        // A token már törölve lett az AuthService-ben
        this.router.navigate(['/login']);
      }
    });
  }
}
