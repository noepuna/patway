import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PersonalDetailsPaneComponent } from './personal-details-pane.component';

describe('PersonalDetailsPaneComponent', () => {
  let component: PersonalDetailsPaneComponent;
  let fixture: ComponentFixture<PersonalDetailsPaneComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PersonalDetailsPaneComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PersonalDetailsPaneComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
