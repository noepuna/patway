import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EnvDesignCriteriaComponent } from './env-design-criteria.component';

describe('EnvDesignCriteriaComponent', () => {
  let component: EnvDesignCriteriaComponent;
  let fixture: ComponentFixture<EnvDesignCriteriaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EnvDesignCriteriaComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EnvDesignCriteriaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
