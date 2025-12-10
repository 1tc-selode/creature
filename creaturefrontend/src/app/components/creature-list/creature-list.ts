import { Component, OnInit } from '@angular/core';
import { CreatureService } from '../../services/creature';
import { Creature } from '../../models/creature';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-creature-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './creature-list.html',
  styleUrl: './creature-list.css',
})
export class CreatureList implements OnInit {
  creatures: Creature[] = [];
  loading: boolean = true;
  searchTerm: string = '';
  isLoggedIn: boolean = false;

  constructor(
    private creatureService: CreatureService,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.isLoggedIn = this.authService.isLoggedIn();
    this.loadCreatures();
  }

  loadCreatures() {
    this.loading = true;
    this.creatureService.getCreatures().subscribe({
      next: (data) => {
        this.creatures = data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading creatures', err);
        this.loading = false;
      }
    });
  }

  deleteCreature(id: number) {
    if (confirm('Biztosan törölni szeretnéd ezt a lényt?')) {
      this.creatureService.deleteCreature(id).subscribe(() => {
        this.creatures = this.creatures.filter(c => c.id !== id);
      });
    }
  }

  get filteredCreatures() {
    return this.creatures.filter(c => 
      c.nev.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
      c.leiras.toLowerCase().includes(this.searchTerm.toLowerCase())
    );
  }
}
