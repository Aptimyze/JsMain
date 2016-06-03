------------------
APIlity Test Suite
------------------

This is a complete test suite which tests all functions in

* Campaign.php
* AdGroup.php
* Keyword.php
* Creative.php
* Report.php
* TrafficEstimate.php

It creates temporary objects it operates on. After the tests
these objects will be COMPLETELY REMOVED. So usually your account's
state remains untouched. The object names are randomously chosen
and are of the form Test_1234 where 1234 is a random number. However,
 there is a very slight chance that one of these Test_1234 names
interferes with one of the names you use in your account.

-------------------------------------------------------------------
Google Inc. is NOT LIABLE for any damage
to your account by running this test suite.
-------------------------------------------------------------------

Also we want to point out that running these tests CONSUMES QUOTA.
The test suite is not designed to be very quota efficient. Its
primary goal is to make sure new releases are consistent.

All tests can be run separately or in an all-in-one test which
takes some time. Some time. We really mean it.

It's fun to see everything's alright anyway...