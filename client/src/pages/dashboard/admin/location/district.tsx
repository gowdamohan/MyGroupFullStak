import DashboardLayout from "@/components/dashboard/DashboardLayout";
import { getActiveMenuItem } from "@/config/admin_menu";
import DistrictManagement from "@/components/content/DistrictManagement";

function LocationDistrict() {
  const menuItems = getActiveMenuItem('/dashboard/admin/location/district');

  return (
    <DashboardLayout
      title="District Management"
      userRole="admin"
      menuItems={menuItems}
    >
      <DistrictManagement />
    </DashboardLayout>
  );
}

export default LocationDistrict;
