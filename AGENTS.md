# AGENTS.md - import-product-tier-price


## Zweck & Verantwortung
Product Tier Price Import für Mengenrabatte und kundengruppen-spezifische Preise. **Tier 5 Modul**.

**Hauptverantwortung:**
- Product Tier Price Import (Quantity-basierte Rabatte)
- Customer Group Pricing Management
- Website-spezifische Tier Prices
- Tier Price Validierung

## Architektur & Design Patterns

### Kern-Klassen
- **ProductTierPriceRepository**: Persistiert Tier Price-Einträge
- **TierPriceProcessor**: Service Layer für Verarbeitung
- **TierPriceValidator**: Validiert Quantity und Preis-Strukturen
- **CustomerGroupResolver**: Mapped Customer Group IDs

### Verwendete Patterns
- **Observer Pattern**: Für Hooks
- **Repository Pattern**: Datenschicht-Abstraktion
- **Service Layer**: Business Logic Isolation

## Abhängigkeiten
- **import-product** ^26.2 - Base Product Importer

## Database Schema

**catalog_product_entity_tier_price** - Tier Price Einträge
- `entity_id` (Product ID)
- `customer_group_id` - 0=all groups, oder spezifische Group ID
- `qty` - Minimum Quantity für Rabatt (z.B. 10)
- `value` - Price für diese Quantity
- `website_id` - Website Kontext

## Common Use Cases

```php
// CSV: Rabatt bei Mengen
// sku,qty_1,price_1,qty_2,price_2
// PROD-001,10,45.00,100,40.00
// Erstellt: 1-9 = normaler Preis, 10-99 = $45, 100+ = $40
```

## Performance Considerations

1. **Customer Group Lookups**: Groups werden mehrfach gesucht
2. **Website-Kontext**: Separate Einträge pro Website
3. **Quantity-Ordering**: Müssen aufsteigend sein für Rabatt-Logik

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Quantity-basiert**: Rabatte bei Mindestmengen
2. **Customer Group-aware**: Verschiedene Preise pro Kundengruppe
3. **Website-spezifisch**: Pro Website unterschiedlich
4. **Tier-Reihenfolge**: Quantities müssen aufsteigend sein

### Häufige Fehler
- ❌ Customer Group IDs nicht validieren
- ❌ Quantities nicht aufsteigend
- ❌ Website-IDs nicht berücksichtigen
- ❌ Tier Prices auf Bundle/Grouped verwenden

## Known Limitations

- **Quantity-Order**: Muss aufsteigend sein (kritisch!)
- **Customer Group**: Abhängig von existierenden Groups
- **Website**: Separate Entries pro Website erforderlich

## Zusammenfassung

**Tier 5 Modul** für Quantity-based Pricing mit Customer Group und Website-Support. Verwaltet Mengenrabatte und kundengruppen-spezifische Preise.

**Für Agenten:** Tier Price Importer mit Quantity und Group-Management.