import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WasteManagementComponent } from './waste-management.component';

describe('WasteManagementComponent', () => {
  let component: WasteManagementComponent;
  let fixture: ComponentFixture<WasteManagementComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WasteManagementComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WasteManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
