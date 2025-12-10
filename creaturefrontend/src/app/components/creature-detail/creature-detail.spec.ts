import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreatureDetail } from './creature-detail';

describe('CreatureDetail', () => {
  let component: CreatureDetail;
  let fixture: ComponentFixture<CreatureDetail>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CreatureDetail]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CreatureDetail);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
