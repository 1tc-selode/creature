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
  selectedFile: File | null = null;
  previewUrl: string | null = null;

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
      meret: ['közepes', Validators.required],
      veszelyesseg: ['alacsony', Validators.required],
      ritkasag: [5, [Validators.required, Validators.min(1), Validators.max(10)]],
      felfedezes_datuma: [''],
      felfedezo: [''],
      allapot: ['aktív', Validators.required],
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
          ritkasag: creature.ritkasag,
          felfedezes_datuma: creature.felfedezes_datuma,
          felfedezo: creature.felfedezo,
          allapot: creature.allapot,
          kategoria_id: creature.kategoria_id
        });
        // Ha van kép URL, jelenítjük meg előnézetként
        if (creature.kep_url) {
          this.previewUrl = creature.kep_url;
        }
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Hiba a lény betöltésekor';
        this.loading = false;
      }
    });
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
      this.selectedFile = file;
      
      // Kép előnézet létrehozása
      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.previewUrl = e.target.result;
      };
      reader.readAsDataURL(file);
    } else if (file) {
      this.error = 'Csak képfájlokat lehet feltölteni!';
      this.selectedFile = null;
      this.previewUrl = null;
    }
  }

  removeImage() {
    this.selectedFile = null;
    this.previewUrl = null;
    // Reset file input
    const fileInput = document.getElementById('kep') as HTMLInputElement;
    if (fileInput) {
      fileInput.value = '';
    }
  }

  onSubmit() {
    if (this.creatureForm.invalid) return;

    this.loading = true;
    const formData = new FormData();
    Object.keys(this.creatureForm.value).forEach(key => {
      if (this.creatureForm.value[key] !== null && this.creatureForm.value[key] !== '') {
        formData.append(key, this.creatureForm.value[key]);
      }
    });

    // Ha van kiválasztott fájl, hozzáadjuk a FormData-hoz
    if (this.selectedFile) {
      formData.append('kep', this.selectedFile);
    }

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
