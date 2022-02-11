import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PermitRegisterComponent } from './permit-register.component';

describe('PermitRegisterComponent', () => {
  let component: PermitRegisterComponent;
  let fixture: ComponentFixture<PermitRegisterComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PermitRegisterComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PermitRegisterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
