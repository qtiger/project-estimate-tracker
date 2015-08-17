# Introduction #
The software is deliberately relatively simple and I have not addressed all potential requirements. As such there are a number of known issues which I have yet addressed. If you find a bug or need a feature let me know.

# Task completion #
Once a task has been completed it is not possible to edit it again. This means that completion estimates cannot be changed.

# Task chaining #
Although it often looks like it, the task view in PET is not a Gantt chart. There is no way to chain dependencies so that a delay in one task has a knock on effect on others. This is partially deliberate because automated systems seldom make the right or appropriate choices. Additionally in its original designed usage, the estimate tracker is concerned more with the validity of individual estimates than it is with the whole project timeline. As such we usually only record tasks in the short term future, rather than the whole project plan.

# Backdating estimates #
It is not possible to backdate estimates. This is by design – but can be annoying if you miss a week.

# Security #
Currently users only need a username to login. All users may edit all items. Anyone may sign-in if they know a user name on the system. For this reason it is strongly recommended that PET only be used in intranet environments (ie behind a firewall) and not exposed as a public URL on the internet.

# Structure #
There is no organisational structure, and no project hierarchy. All users are seen as the same level and it is not possible to divide projects into sub-projects. There is now a team field on user. Currently the only place where this is applied is in the data export for use in a pivot table.

# Multi User Tasks #
PET does not support multi-user tracked tasks. Either create one task and assign a lead staff member or create one task per staff member. Untracked tasks may be assigned to specific users to the user general, which means that they show on all user’s timesheets.

# Transferring tasks to a different user #
When a task is moved to a different user PET updates the task but does not record the change. It will look as if that was always assigned to that user. If the transfer needs to be noted the best option is to complete the task for the initial user and create a new task for the new user.

# Minutes calculation issues #
The routine which calculates minutes is designed for simplicity not cleverness. Entering 1.5 adds one hour and five minutes (65 minutes) to the task. Entering 1.75 adds one hour and seventy five minutes to the task (ie two hours and fifteen minutes). If you stick to adding hours and minutes in the form hh:mm it will work just fine.