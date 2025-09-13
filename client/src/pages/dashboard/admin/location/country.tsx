import React from "react";
import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import CountryManagement from "@/components/content/CountryManagement";

function LocationCountry() {
  const menuItems = getActiveMenuItem('/dashboard/admin/location/country');

  return (
    <DashboardLayout
      title="Country Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <CountryManagement />
    </DashboardLayout>
  );
}

export default LocationCountry;
