import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ModularizationComponent } from './modularization.component';

describe('ModularizationComponent', () => {
  let component: ModularizationComponent;
  let fixture: ComponentFixture<ModularizationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ModularizationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ModularizationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
