# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.1.0] - 2025-11-29

### Added
- Synchronized `README.md` with `README_TW.md` by adding "Frontend Integration" and "Server-side Redirect" sections.

## [2.0.0] - 2025-11-28

### Added
- PHP 8.3 support.
- `LgsType`, `ShipType`, `TradeType`, `RespondType`, `Version` Backed Enums.
- `Validator` class for unified parameter validation.
- `send()` method in `NewebPayLogistics` for unified request handling.
- Dedicated `Request` and `Response` classes for each operation.
- `#[Override]` attributes for improved code quality.
- Constructor Property Promotion and `readonly` properties in `NewebPayLogistics` and `BaseRequest`.
- Comprehensive unit tests and updated examples.

### Changed
- **BREAKING**: Minimum PHP version raised to 8.3.
- **BREAKING**: Refactored `NewebPayLogistics` to use Dependency Injection for `ClientInterface`.
- **BREAKING**: Renamed `Content` class to `BaseRequest`.
- **BREAKING**: Moved operation classes to `CarlLee\NewebPayLogistics\Requests` namespace.
- **BREAKING**: Parameter classes (`LgsType`, etc.) are now Enums, requiring usage like `LgsType::B2C` instead of `LgsType::B2C`.
- Updated `README.md` and `README_TW.md` to reflect the new API and PHP 8.3 requirements.

## [1.0.0] - 2025-11-28

### Added
- Initial release.
- Support for Map, Create Order, Query Order, and Print Order operations.
- PHP 7.4 compatibility.
- Docker environment for development.
