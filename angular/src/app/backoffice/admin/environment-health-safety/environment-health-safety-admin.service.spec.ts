import { TestBed } from '@angular/core/testing';

import { EnvironmentHealthSafetyAdminService } from './environment-health-safety-admin.service';

describe('EnvironmentHealthSafetyAdminService', () => {
  let service: EnvironmentHealthSafetyAdminService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EnvironmentHealthSafetyAdminService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
