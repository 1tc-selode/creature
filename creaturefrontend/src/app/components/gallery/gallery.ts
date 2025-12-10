import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouterModule } from '@angular/router';
import { CreatureService } from '../../services/creature';
import { GalleryImage } from '../../models/creature';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-gallery',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './gallery.html',
  styleUrl: './gallery.css',
})
export class Gallery implements OnInit {
  images: GalleryImage[] = [];
  creatureId: number | null = null;
  loading: boolean = true;
  isLoggedIn: boolean = false;
  selectedImage: GalleryImage | null = null;

  constructor(
    private route: ActivatedRoute,
    private creatureService: CreatureService,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.isLoggedIn = this.authService.isLoggedIn();
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.creatureId = +id;
      this.loadGallery(this.creatureId);
    }
  }

  loadGallery(id: number) {
    this.loading = true;
    this.creatureService.getGallery(id).subscribe({
      next: (data) => {
        this.images = data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading gallery', err);
        this.loading = false;
      }
    });
  }

  onFileSelected(event: any) {
    if (!this.creatureId) return;
    
    const file: File = event.target.files[0];
    if (file) {
      this.creatureService.uploadGalleryImage(this.creatureId, file).subscribe({
        next: (image) => {
          this.images.push(image);
        },
        error: (err) => {
          alert('Hiba a kép feltöltésekor');
        }
      });
    }
  }

  deleteImage(imageId: number) {
    if (!this.creatureId) return;
    
    if (confirm('Biztosan törölni szeretnéd ezt a képet?')) {
      this.creatureService.deleteGalleryImage(this.creatureId, imageId).subscribe({
        next: () => {
          this.images = this.images.filter(img => img.id !== imageId);
          if (this.selectedImage?.id === imageId) {
            this.selectedImage = null;
          }
        },
        error: (err) => {
          alert('Hiba a kép törlésekor');
        }
      });
    }
  }

  openLightbox(image: GalleryImage) {
    this.selectedImage = image;
  }

  closeLightbox() {
    this.selectedImage = null;
  }
}
