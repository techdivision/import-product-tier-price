# AGENTS.md - import-product-tier-price

## Zweck & Verantwortung

Das `import-product-tier-price` Modul bietet **Product Tier Price Import-Funktionalität**. Es ist ein **Tier 5 Modul** und erweitert `import-product`.

**Hauptverantwortung:**
- Tier Price Import
- Customer Group Pricing
- Repository Pattern für Tier Prices
- Service Layer für Tier Price-Verarbeitung
- Observer Pattern für Tier Price-Hooks
- Event-Driven für Bulk Operations

## Architektur & Design Patterns

### Kern-Klassen
- **TierPriceRepository**: Persistierung von Tier Prices
- **TierPriceProcessor**: Service Layer
- **TierPriceObserver**: Observer für Hooks

### Verwendete Patterns
- **Observer Pattern**: Für Tier Price-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic
- **Event-Driven**: Für Bulk Operations

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product** ^26.2 - Product Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-cli-simple** - Master CLI

## Wichtige Entry Points

### Repository Klassen
```php
// Tier Price Repository
TierPriceRepository::create($row): void
TierPriceRepository::findByProductId($productId): array
```

## Events & Extension Points

### Events
- **BeforeTierPriceCreateEvent**: Vor Tier Price-Create
- **AfterTierPriceCreateEvent**: Nach Tier Price-Create

### Listeners
- **TierPriceCacheListener**: Für Tier Price-Caching

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 5 Modul**: Erweitert Product Importer
2. **Tier Price-fokussiert**: Spezialisiert auf Tier Prices
3. **Observer Pattern**: Für Hooks
4. **Repository Pattern**: Für Persistierung
5. **Event-Driven**: Für Bulk Operations

## Bekannte Einschränkungen

- **Tier Price-Only**: Keine anderen Pricing-Features
- **Abhängig von Products**: Erfordert Products zu existieren

## Zusammenfassung

`import-product-tier-price` ist ein **Tier 5 Modul**, das Product Tier Price Import-Funktionalität bietet. Es erweitert den Product Importer mit spezialisierter Funktionalität für Tier Prices.

**Für Agenten:** Verstehe dieses Modul als **Tier Price Importer** mit Observer, Repository Pattern, und Event-Driven Architektur.
