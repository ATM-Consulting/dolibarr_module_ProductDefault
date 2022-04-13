-- Copyright (C) ---Put here your own copyright and developer email---
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see https://www.gnu.org/licenses/.


CREATE TABLE llx_productdefault_productthirdpartydefault(
    rowid                           integer AUTO_INCREMENT PRIMARY KEY,
    entity                          integer         DEFAULT 1,
    fk_soc                          integer         NOT NULL,
    fk_product						integer         NULL,
    label                           varchar(255)    DEFAULT NULL,
    description                     text,
    fk_remise_except                integer         NULL,           -- Link to table of fixed discounts
    vat_src_code                    varchar(10)     DEFAULT '',		-- Vat code used as source of vat fields. Not strict foreign key here.
    tva_tx                          double(6,3)     DEFAULT 0, 	    -- Vat rate
    localtax1_tx                    double(6,3)     DEFAULT 0,    	-- localtax1 rate
    localtax1_type                  varchar(10)	    NULL,           -- localtax1 type
    localtax2_tx               		double(6,3)     DEFAULT 0,      -- localtax2 rate
    localtax2_type			 		varchar(10)     NULL,           -- localtax2 type
    qty								real,                           -- quantity
    remise_percent					real            DEFAULT 0,      -- discount percentage
    remise							real            DEFAULT 0,      -- discount amount (obsolete)
    price                           real,                           -- final price (obsolete)
    subprice                        double(24,8)    DEFAULT 0,      -- unit price article
    total_ht                        double(24,8)    DEFAULT 0,      -- Total excluding VAT of the line all quantities and including line and global discount
    total_tva                       double(24,8)    DEFAULT 0,      -- Total VAT of the line any quantity and including discount line and global
    total_localtax1					double(24,8)    DEFAULT 0,      -- Total localtax1
    total_localtax2					double(24,8)    DEFAULT 0,      -- Total localtax2
    total_ttc                       double(24,8)    DEFAULT 0,      -- Total TTC of the line all quantity and including line and global discount
    product_type                    integer         DEFAULT 0,      -- 0 or 1. Value 9 may be used by some modules (amount of line may not be included into generated discount if value is 9).
    date_start						datetime        DEFAULT NULL,   -- start date if service
    date_end                        datetime        DEFAULT NULL,   -- end date if service
    info_bits                       integer         DEFAULT 0,      -- VAT NPR or not
    buy_price_ht					double(24,8)    DEFAULT 0,      -- buying price
    fk_product_fournisseur_price	integer         DEFAULT NULL,   -- reference of supplier price when line was added (may be used to update buy_price_ht current price when future invoice will be created)
    special_code					integer         DEFAULT 0,      -- code for special lines (may be 1=transport, 2=ecotax, 3=option, moduleid=...)
    rang							integer         DEFAULT 0,      -- order display on the propal
    fk_unit                         integer         DEFAULT NULL,   -- link to table of units
    fk_multicurrency                integer,
    multicurrency_code              varchar(255),
    multicurrency_subprice          double(24,8)    DEFAULT 0,
    multicurrency_total_ht          double(24,8)    DEFAULT 0,
    multicurrency_total_tva         double(24,8)    DEFAULT 0,
    multicurrency_total_ttc         double(24,8)    DEFAULT 0,
    import_key                      varchar(14)
) ENGINE=innodb;
