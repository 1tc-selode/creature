import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CreatureService } from '../../services/creature';
import { Category, Ability } from '../../models/creature';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-creature-form',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule, RouterModule],
  templateUrl: './creature-form.html',
  styleUrl: './creature-form.css',
})
export class CreatureForm implements OnInit {
  creatureForm: FormGroup;
  isEditMode: boolean = false;
  creatureId: number | null = null;
  categories: Category[] = [];
  abilities: Ability[] = [];
  loading: boolean = false;
  error: string = '';

  constructor(
    private fb: FormBuilder,
    private creatureService: CreatureService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.creatureForm = this.fb.group({
      nev: ['', Validators.required],
      tudomanyos_nev: [''],
      leiras: ['', Validators.required],
      elohely: [''],
      meret: [''],
      veszelyesseg: [''],
      kategoria_id: ['', Validators.required],
    });
  }

  ngOnInit() {
    this.loadCategories();
    this.loadAbilities();

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.isEditMode = true;
      this.creatureId = +id;
      this.loadCreature(this.creatureId);
    }
  }

  loadCategories() {
    this.creatureService.getCategories().subscribe(data => this.categories = data);
  }

  loadAbilities() {
    this.creatureService.getAbilities().subscribe(data => this.abilities = data);
  }

  loadCreature(id: number) {
    this.loading = true;
    this.creatureService.getCreature(id).subscribe({
      next: (creature) => {
        this.creatureForm.patchValue({
          nev: creature.nev,
          tudomanyos_nev: creature.tudomanyos_nev,
          leiras: creature.leiras,
          elohely: creature.elohely,
          meret: creature.meret,
          veszelyesseg: creature.veszelyesseg,
          kategoria_id: creature.kategoria_id
        });
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Hiba a lény betöltésekor';
        this.loading = false;
      }
    });
  }

  onSubmit() {
    if (this.creatureForm.invalid) return;

    this.loading = true;
    const formData = new FormData();
    Object.keys(this.creatureForm.value).forEach(key => {
      formData.append(key, this.creatureForm.value[key]);
    });

    if (this.isEditMode && this.creatureId) {
      this.creatureService.updateCreature(this.creatureId, formData).subscribe({
        next: () => this.router.navigate(['/creatures', this.creatureId]),
        error: (err) => {
          this.error = 'Hiba a mentés során';
          this.loading = false;
        }
      });
    } else {
      this.creatureService.createCreature(formData).subscribe({
        next: () => this.router.navigate(['/creatures']),
        error: (err) => {
          this.error = 'Hiba a létrehozás során';
          this.loading = false;
        }
      });
    }
  }
}
