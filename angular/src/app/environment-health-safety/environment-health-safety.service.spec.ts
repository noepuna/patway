import { TestBed } from '@angular/core/testing';

import { EnvironmentHealthSafetyService } from './environment-health-safety.service';

describe('EnvironmentHealthSafetyService', () => {
  let service: EnvironmentHealthSafetyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EnvironmentHealthSafetyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
