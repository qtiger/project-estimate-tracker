# v0.2.0 Database Hardening #

Work has is now well under-way to revisit every user entry form and review the way that user input is handled in order to improve the database & server security. This includes

  * Rationalisation of the way SQL calls are handled
  * Remove reliance on cookies for storing login data
  * Tighter validation of form and query string data
  * Robust treatment of special characters in input data (both to ensure correct display and to increase security)
  * Optimisation of certain inefficient code routines

Additionally there are a couple of minor enhancements that will be rolled into the v0.2.0 release:

  * Support for American date formats
  * Replacing all hard-coded user interface messages with string constants to enable for easier localisation


---

# v0.3.0 User Security #

Once the data hardening has been done, it is intended to add proper user security to force a user-name & password to be required in order to view/update PET. At this stage I may also review whether to have role levels (eg normal user who can only edit their own items and supervisor who can edit all). Ideally it would also be good to link with any existing login systems (eg OpenID, LDAP etc), but this is much less likely to happen, unless specific requests are made for it.

With current progress it is likely that the v0.3.0 release will be rolled into the v0.2.0 release as there are some functions that make sense to do at the same time


---

# Template Review #

PET is the only project I've ever done with Smarty. As such the way that the templates have been constructed is a bit convoluted. It would be nice to simplify them either within Smarty, or move to a much lighter model using pure PHP templates, which has been my preferred approach on subsequent projects.