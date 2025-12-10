import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Creature, Category, Ability, ContactMessage, GalleryImage } from '../models/creature';

@Injectable({
  providedIn: 'root'
})
export class CreatureService {
  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) { }

  // Creatures
  getCreatures(params?: any): Observable<Creature[]> {
    let httpParams = new HttpParams();
    if (params) {
      Object.keys(params).forEach(key => {
        if (params[key]) {
          httpParams = httpParams.set(key, params[key]);
        }
      });
    }
    return this.http.get<Creature[]>(`${this.apiUrl}/creatures`, { params: httpParams });
  }

  getCreature(id: number): Observable<Creature> {
    return this.http.get<Creature>(`${this.apiUrl}/creatures/${id}`);
  }

  createCreature(creature: FormData): Observable<Creature> {
    return this.http.post<Creature>(`${this.apiUrl}/creatures`, creature);
  }

  updateCreature(id: number, creature: FormData): Observable<Creature> {
    // For PUT requests with FormData, Laravel sometimes needs _method: PUT
    creature.append('_method', 'PUT');
    return this.http.post<Creature>(`${this.apiUrl}/creatures/${id}`, creature);
  }

  deleteCreature(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${id}`);
  }

  // Categories & Abilities
  getCategories(): Observable<Category[]> {
    return this.http.get<Category[]>(`${this.apiUrl}/categories`);
  }

  getAbilities(): Observable<Ability[]> {
    return this.http.get<Ability[]>(`${this.apiUrl}/abilities`);
  }

  attachAbility(creatureId: number, abilityId: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/creatures/${creatureId}/abilities`, { ability_id: abilityId });
  }

  detachAbility(creatureId: number, abilityId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${creatureId}/abilities/${abilityId}`);
  }

  // Gallery
  getGallery(creatureId: number): Observable<GalleryImage[]> {
    return this.http.get<GalleryImage[]>(`${this.apiUrl}/creatures/${creatureId}/gallery`);
  }

  uploadGalleryImage(creatureId: number, image: File): Observable<GalleryImage> {
    const formData = new FormData();
    formData.append('image', image);
    return this.http.post<GalleryImage>(`${this.apiUrl}/creatures/${creatureId}/gallery`, formData);
  }

  deleteGalleryImage(creatureId: number, imageId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${creatureId}/gallery/${imageId}`);
  }

  // Contact
  sendContactMessage(message: ContactMessage): Observable<any> {
    return this.http.post(`${this.apiUrl}/contact`, message);
  }
}
