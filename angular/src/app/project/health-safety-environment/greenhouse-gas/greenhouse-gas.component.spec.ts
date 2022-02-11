import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GreenhouseGasComponent } from './greenhouse-gas.component';

describe('GreenhouseGasComponent', () => {
  let component: GreenhouseGasComponent;
  let fixture: ComponentFixture<GreenhouseGasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GreenhouseGasComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GreenhouseGasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
