import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StaffAccountInputComponent } from './staff-account-input.component';

describe('StaffAccountInputComponent', () => {
  let component: StaffAccountInputComponent;
  let fixture: ComponentFixture<StaffAccountInputComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ StaffAccountInputComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(StaffAccountInputComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
