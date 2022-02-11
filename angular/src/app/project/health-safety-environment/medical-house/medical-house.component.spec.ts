import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MedicalHouseComponent } from './medical-house.component';

describe('MedicalHouseComponent', () => {
  let component: MedicalHouseComponent;
  let fixture: ComponentFixture<MedicalHouseComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MedicalHouseComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MedicalHouseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
