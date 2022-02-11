import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConstructibilityComponent } from './constructibility.component';

describe('ConstructibilityComponent', () => {
  let component: ConstructibilityComponent;
  let fixture: ComponentFixture<ConstructibilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ConstructibilityComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ConstructibilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
