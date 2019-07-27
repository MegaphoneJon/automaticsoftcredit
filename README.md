# automaticsoftcredit

This extension allows you to define automatic soft credits based on a contact's relationship.

Some use cases:
* Any time a person gives, automatically soft credit their spouse and/or household.
* A member of your Board of Directors sends a list of people they're reaching out to.  You want any gifts by that person to automatically soft credit your Board member.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.6+
* CiviCRM 5.15+

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl automaticsoftcredit@https://github.com/MegaphoneJon/automaticsoftcredit/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/MegaphoneJon/automaticsoftcredit.git
cv en automaticsoftcredit
```

## Usage

Upon installation, your relationship types will all gain two new custom fields.  Find them by going to **Administer menu » Customize Data and Screens » Relationship Types** and clicking **Edit** next to the relationship type you'd like to control a soft credit.

* **Soft Credit Type**: If this field is filled in, a soft credit will be created whenever someone with this relationship makes a contribution (subject to Soft Credit Direction).
* **Soft Credit Direction**: This controls the direction in which automatic soft credits flow.  E.g. credit employers when employees give, but don't credit employees when employers give.

![Screenshot of Relationship Type Edit screen](/images/Selection_952.png)
