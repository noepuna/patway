import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EnvProtectionPlanComponent } from './env-protection-plan.component';

describe('EnvProtectionPlanComponent', () => {
  let component: EnvProtectionPlanComponent;
  let fixture: ComponentFixture<EnvProtectionPlanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EnvProtectionPlanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EnvProtectionPlanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
