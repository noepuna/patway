import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DrugAlcoholComponent } from './drug-alcohol.component';

describe('DrugAlcoholComponent', () => {
  let component: DrugAlcoholComponent;
  let fixture: ComponentFixture<DrugAlcoholComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DrugAlcoholComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DrugAlcoholComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
