import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfilePhotoPaneComponent } from './profile-photo-pane.component';

describe('ProfilePhotoPaneComponent', () => {
  let component: ProfilePhotoPaneComponent;
  let fixture: ComponentFixture<ProfilePhotoPaneComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProfilePhotoPaneComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ProfilePhotoPaneComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
