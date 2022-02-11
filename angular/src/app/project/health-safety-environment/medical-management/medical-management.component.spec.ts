import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MedicalManagementComponent } from './medical-management.component';

describe('MedicalManagementComponent', () => {
  let component: MedicalManagementComponent;
  let fixture: ComponentFixture<MedicalManagementComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MedicalManagementComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(MedicalManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
