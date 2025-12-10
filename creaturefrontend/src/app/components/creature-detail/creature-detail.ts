import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouterModule } from '@angular/router';
import { CreatureService } from '../../services/creature';
import { Creature } from '../../models/creature';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-creature-detail',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './creature-detail.html',
  styleUrl: './creature-detail.css',
})
export class CreatureDetail implements OnInit {
  creature: Creature | null = null;
  loading: boolean = true;
  isLoggedIn: boolean = false;

  constructor(
    private route: ActivatedRoute,
    private creatureService: CreatureService,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.isLoggedIn = this.authService.isLoggedIn();
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.loadCreature(+id);
    }
  }

  loadCreature(id: number) {
    this.creatureService.getCreature(id).subscribe({
      next: (data) => {
        this.creature = data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading creature', err);
        this.loading = false;
      }
    });
  }
}
