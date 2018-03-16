# silverstripe-bitly
Provides [Bitly](https://bitly.com) url generation for all pages.

## Requirements
* silverstripe/cms: ^4

## Installation
First, install with composer and then configure your API key

```bash
$ composer require vulcandigital/silverstripe-bitly
```

**mysite/_config/bitly.yml**
```yaml
Vulcan\Bitly\Bitly:
  api_key: 'YOUR_ACCESS_TOKEN'
```
Afterwards, run `dev/build`

## Screenshots
### Default
![Bitly Field Unset](https://i.imgur.com/8Y0htXg.png)

### Generated
![Bitly Field](https://i.imgur.com/ns4jE7Y.png)

### Segment Warning
![Bitly Field](https://i.imgur.com/mfErVjK.png)

### Segment Changed Notice
![Bitly Field](https://i.imgur.com/EouQK6T.png)

## License
[BSD-3-Clause](LICENSE.md) - [Vulcan Digital Ltd](https://vulcandigital.co.nz)