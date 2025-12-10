export interface Category {
  id: number;
  nev: string;
  leiras?: string;
  created_at?: string;
  updated_at?: string;
}

export interface Ability {
  id: number;
  nev: string;
  leiras?: string;
  tipus?: string;
  created_at?: string;
  updated_at?: string;
}

export interface GalleryImage {
  id: number;
  fajl_nev: string;
  cim?: string;
  leiras?: string;
  leny_id: number;
  created_at?: string;
  updated_at?: string;
}

export interface Creature {
  id: number;
  nev: string;
  tudomanyos_nev?: string;
  leiras: string;
  elohely?: string;
  meret?: string;
  veszelyesseg?: string;
  ritkasag?: number;
  felfedezes_datuma?: string;
  felfedezo?: string;
  allapot?: string;
  extra_adatok?: any;
  kep_url?: string;
  user_id: number;
  kategoria_id: number;
  kategoria?: Category;
  kepessegek?: Ability[];
  galeria?: GalleryImage[];
  created_at?: string;
  updated_at?: string;
}

export interface ContactMessage {
  nev: string;
  email: string;
  uzenet: string;
}
