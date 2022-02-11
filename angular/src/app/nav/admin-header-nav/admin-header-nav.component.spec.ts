import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminHeaderNavComponent } from './admin-header-nav.component';

describe('AdminHeaderNavComponent', () => {
  let component: AdminHeaderNavComponent;
  let fixture: ComponentFixture<AdminHeaderNavComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AdminHeaderNavComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AdminHeaderNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
