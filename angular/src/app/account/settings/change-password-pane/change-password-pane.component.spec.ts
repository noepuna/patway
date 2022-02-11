import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangePasswordPaneComponent } from './change-password-pane.component';

describe('ChangePasswordPaneComponent', () => {
  let component: ChangePasswordPaneComponent;
  let fixture: ComponentFixture<ChangePasswordPaneComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ChangePasswordPaneComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ChangePasswordPaneComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
