import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
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
    return this.http.get<any>(`${this.apiUrl}/creatures`, { params: httpParams }).pipe(
      map(response => response.data?.data || [])
    );
  }

  getCreature(id: number): Observable<Creature> {
    return this.http.get<any>(`${this.apiUrl}/creatures/${id}`).pipe(
      map(response => response.data)
    );
  }

  createCreature(creature: FormData): Observable<Creature> {
    return this.http.post<any>(`${this.apiUrl}/creatures`, creature).pipe(
      map(response => response.data)
    );
  }

  updateCreature(id: number, creature: FormData): Observable<Creature> {
    // For PUT requests with FormData, Laravel sometimes needs _method: PUT
    creature.append('_method', 'PUT');
    return this.http.post<any>(`${this.apiUrl}/creatures/${id}`, creature).pipe(
      map(response => response.data)
    );
  }

  deleteCreature(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${id}`);
  }

  // Categories & Abilities
  getCategories(): Observable<Category[]> {
    return this.http.get<any>(`${this.apiUrl}/categories`).pipe(
      map(response => response.data || [])
    );
  }

  getAbilities(): Observable<Ability[]> {
    return this.http.get<any>(`${this.apiUrl}/abilities`).pipe(
      map(response => response.data || [])
    );
  }

  attachAbility(creatureId: number, abilityId: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/creatures/${creatureId}/abilities`, { ability_id: abilityId });
  }

  detachAbility(creatureId: number, abilityId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${creatureId}/abilities/${abilityId}`);
  }

  // Gallery
  getGallery(creatureId: number): Observable<GalleryImage[]> {
    return this.http.get<any>(`${this.apiUrl}/creatures/${creatureId}/gallery`).pipe(
      map(response => response.data || [])
    );
  }

  uploadGalleryImage(creatureId: number, image: File): Observable<GalleryImage> {
    const formData = new FormData();
    formData.append('image', image);
    return this.http.post<any>(`${this.apiUrl}/creatures/${creatureId}/gallery`, formData).pipe(
      map(response => response.data)
    );
  }

  deleteGalleryImage(creatureId: number, imageId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/creatures/${creatureId}/gallery/${imageId}`);
  }

  // Contact
  sendContactMessage(message: ContactMessage): Observable<any> {
    return this.http.post(`${this.apiUrl}/contact`, message);
  }
}
