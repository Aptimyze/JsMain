update billing.PURCHASES SET SERVICE_TAX_CONTENT='(Inclusive of Service Tax, as applicable)' WHERE CUR_TYPE='DOL'

update billing.REV_MASTER SET SERVICE_TAX_CONTENT='(Inclusive of Service Tax, as applicable)' WHERE CUR_TYPE='DOL'
