import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EarlySafetyComponent } from './early-safety.component';

describe('EarlySafetyComponent', () => {
  let component: EarlySafetyComponent;
  let fixture: ComponentFixture<EarlySafetyComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EarlySafetyComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EarlySafetyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
