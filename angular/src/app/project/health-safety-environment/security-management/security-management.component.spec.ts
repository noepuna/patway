import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SecurityManagementComponent } from './security-management.component';

describe('SecurityManagementComponent', () => {
  let component: SecurityManagementComponent;
  let fixture: ComponentFixture<SecurityManagementComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SecurityManagementComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SecurityManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
