import { TestBed } from '@angular/core/testing';

import { Creature } from './creature';

describe('Creature', () => {
  let service: Creature;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(Creature);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
