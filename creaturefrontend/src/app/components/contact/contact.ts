import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CreatureService } from '../../services/creature';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './contact.html',
  styleUrl: './contact.css',
})
export class Contact {
  contactForm: FormGroup;
  loading: boolean = false;
  successMessage: string = '';
  errorMessage: string = '';

  constructor(
    private fb: FormBuilder,
    private creatureService: CreatureService
  ) {
    this.contactForm = this.fb.group({
      nev: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      uzenet: ['', Validators.required]
    });
  }

  onSubmit() {
    if (this.contactForm.invalid) return;

    this.loading = true;
    this.successMessage = '';
    this.errorMessage = '';

    this.creatureService.sendContactMessage(this.contactForm.value).subscribe({
      next: () => {
        this.successMessage = 'Üzenet sikeresen elküldve!';
        this.contactForm.reset();
        this.loading = false;
      },
      error: (err) => {
        this.errorMessage = 'Hiba az üzenet küldésekor. Kérjük próbáld újra később.';
        this.loading = false;
      }
    });
  }
}
